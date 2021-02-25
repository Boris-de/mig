#!/bin/sh

set -e

die() {
  echo "${@}" 1>&2
  exit 1
}

create_thumb() {
  _image="${1}"
  _thumbs_dir=$(dirname "${_image}")/thumbs
  mkdir -p "${_thumbs_dir}"
  convert "${_image}" -resize 128 "${_thumbs_dir}/$(basename "${_image}")"
}

random_image() {
  _mx=320
  _my=256
  head -c "$((3*_mx*_my))" /dev/urandom | convert -depth 8 -size "${_mx}x${_my}" RGB:- "${1}"
}

random_image_with_thumb() {
  random_image "${1}"
  create_thumb "${1}"
}

simple_folder() {
  mkdir "${1}"
  random_image_with_thumb "${1}/${1}.jpg"
  echo "UseThumb ${1} ${1}.jpg" >> mig.cf
}

test -z "${1}" && die "usage: ${0} directory"
mkdir "${1}" || die "could not create directory"

cd "${1}"

random_image_with_thumb image1.jpg
random_image image2.png
random_image hidden_image.jpg
ffmpeg -hide_banner -loglevel panic -i image2.png video.mp4
touch audio.mp3

cat > mig.cf << EOF
<Bulletin>
Example Bulletin text
</Bulletin>

<Comment "image1.jpg">
random example with a thumbnail
</Comment>

<Comment "image2.png">
random example without a thumbnail
</Comment>

#testcase: short comment should override comment
<Short "image2.png">
short comment
</Short>

#testcase: hidden file
<hidden>
hidden_image.jpg
</hidden>
EOF

for i in $(seq 1 9); do
  FOLDER_NAME=folder${i}
  mkdir "${FOLDER_NAME}"
  (
    cd "${FOLDER_NAME}"

    random_image_with_thumb "image_${i}_1.jpg"
    random_image "image_${i}_2.jpg"
  )

  # testcase: use first image's thumbnail for the folder
  echo "UseThumb ${FOLDER_NAME} image_${i}_1.jpg" >> mig.cf
done

simple_folder "encoding_Łódź"
simple_folder "encoding_麻婆豆腐"
simple_folder "encoding_emoji🤷"
simple_folder "encoding&test"

# testcase: change sorting in "folder1"
cat > folder1/mig.cf <<EOF
<sort>
image_1_2.jpg
image_1_1.jpg
</sort>
EOF
